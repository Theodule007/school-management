<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Guardian;
use App\Models\Level;
use App\Models\LevelSection;
use App\Models\Section;
use App\Models\Student;
use DomainException;
use Illuminate\Http\Request;
use App\Services\Student\StudentService;
use Exception;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    protected $studentService;

    // Injection via constructeur
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['guardians' => function ($q) {
            $q->wherePivot('is_primary', true);
        }])
            ->with('section');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('nisu', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
            });
        }

        // FILTRE PAR NIVEAU - via les inscriptions
        if ($request->filled('level_id')) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->whereHas('levelSection', function ($sq) use ($request) {
                    $sq->where('level_id', $request->level_id);
                });
            });
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        $levels = Level::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('students.index', compact('students', 'levels', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $guardians = Guardian::orderBy('last_name')->orderBy('first_name')->get();
        $levels = Level::get();
        $sections = Section::get();

        return view('students.create', compact('academicYears', 'guardians', 'levels', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Enregistrer un nouvel étudiant
     */
    public function store(Request $request)
    {
        // Validation de base pour l'étudiant
        $rules = [
            'nisu' => 'required|string|unique:students,nisu',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'section_id' => 'required|exists:sections,id',
            'student_status' => 'required|in:active,archived',
            'guardian_mode' => 'required|in:existing,new',

            // Inscription optionnelle
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'level_section_id' => 'nullable|exists:level_sections,id'
        ];

        // Ajouter les règles selon le mode choisi
        if ($request->guardian_mode === 'existing') {
            $rules = array_merge($rules, [
                'guardian_id_1' => 'required|exists:guardians,id|different:guardian_id_2',
                'guardian_id_2' => 'required|exists:guardians,id|different:guardian_id_1',
            ]);
        } else {
            $rules = array_merge($rules, [
                'guardian1_first_name' => 'required|string|max:255',
                'guardian1_last_name' => 'required|string|max:255',
                'guardian1_phone' => 'required|string|max:20',
                'guardian1_email' => 'nullable|email|max:255',
                'guardian1_address' => 'nullable|string',

                'guardian2_first_name' => 'required|string|max:255',
                'guardian2_last_name' => 'required|string|max:255',
                'guardian2_phone' => 'required|string|max:20',
                'guardian2_email' => 'nullable|email|max:255',
                'guardian2_address' => 'nullable|string',
            ]);
        }

        $messages = [
            'required' => 'Le champ :attribute est obligatoire.',
            'unique' => 'Cette valeur est déjà utilisée.',
            'exists' => 'La valeur sélectionnée n\'existe pas.',
            'guardian_id_1.required' => 'Veuillez sélectionner le premier tuteur.',
            'guardian_id_2.required' => 'Veuillez sélectionner le deuxième tuteur.',
            'guardian_id_1.different' => 'Les deux tuteurs doivent être différents.',
        ];

        $attributes = [
            'nisu' => 'NISU',
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'gender' => 'genre',
            'section_id' => 'section par défaut',
        ];

        try {
            $validated = $request->validate($rules, $messages, $attributes);

            DB::beginTransaction();

            // 1. Créer l'étudiant
            $student = Student::create([
                'nisu' => $validated['nisu'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'section_id' => $validated['section_id'],
                'status' => $validated['student_status']
            ]);

            // 2. Gérer les tuteurs selon le mode
            if ($request->guardian_mode === 'existing') {
                // Utiliser des tuteurs existants
                $guardianIds = [$validated['guardian_id_1'], $validated['guardian_id_2']];
                $this->studentService->attachGuardians($student, $guardianIds);
            } else {
                // Créer de nouveaux tuteurs
                $guardian1 = Guardian::create([
                    'first_name' => $validated['guardian1_first_name'],
                    'last_name' => $validated['guardian1_last_name'],
                    'phone' => $validated['guardian1_phone'],
                    'email' => $validated['guardian1_email'] ?? null,
                    'address' => $validated['guardian1_address'] ?? null,
                    'status' => 'active'
                ]);

                $guardian2 = Guardian::create([
                    'first_name' => $validated['guardian2_first_name'],
                    'last_name' => $validated['guardian2_last_name'],
                    'phone' => $validated['guardian2_phone'],
                    'email' => $validated['guardian2_email'] ?? null,
                    'address' => $validated['guardian2_address'] ?? null,
                    'status' => 'active'
                ]);

                $student->guardians()->attach([
                    $guardian1->id => ['relationship' => 'tuteur', 'is_primary' => true],
                    $guardian2->id => ['relationship' => 'tuteur', 'is_primary' => false]
                ]);
            }

            // 3. Créer l'inscription si demandée
            if (!empty($validated['academic_year_id']) && !empty($validated['level_section_id'])) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $validated['academic_year_id'],
                    'level_section_id' => $validated['level_section_id'],
                    'status' => 'enrolled'
                ]);
            }

            DB::commit();

            return redirect()->route('students.show', $student)
                ->with('success', 'Étudiant créé avec succès.');
        } catch (DomainException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['guardians' => $e->getMessage()]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    public function storeWithGuardians(Request $request)
    {
        $rules = [
            // Étudiant
            'nisu' => 'required|string|max:255|unique:students,nisu',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'section_id' => 'required|exists:sections,id',
            'student_status' => 'required|in:active,archived',

            // Responsable 1
            'guardian1_first_name' => 'required|string|max:255',
            'guardian1_last_name' => 'required|string|max:255',
            'guardian1_phone' => 'required|string|max:20',
            'guardian1_email' => 'nullable|email|max:255',
            'guardian1_address' => 'nullable|string',
            'guardian1_relationship' => 'required|in:pere,mere,tuteur,autre',

            // Responsable 2
            'guardian2_first_name' => 'required|string|max:255',
            'guardian2_last_name' => 'required|string|max:255',
            'guardian2_phone' => 'required|string|max:20',
            'guardian2_email' => 'nullable|email|max:255',
            'guardian2_address' => 'nullable|string',
            'guardian2_relationship' => 'required|in:pere,mere,tuteur,autre',

            // Inscription optionnelle
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'level_section_id' => 'nullable|exists:level_sections,id',
        ];

        // Messages personnalisés en français
        $messages = [
            'required' => 'Le champ :attribute est obligatoire.',
            'unique' => 'Cette valeur est déjà utilisée.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'exists' => 'La valeur sélectionnée n\'existe pas.',
            'in' => 'La valeur sélectionnée n\'est pas valide.',
            'nisu.unique' => 'Ce numéro NISU est déjà attribué à un autre étudiant.',
        ];

        // Noms des attributs pour les messages d'erreur
        $attributes = [
            'nisu' => 'NISU',
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'gender' => 'genre',
            'birth_date' => 'date de naissance',
            'section_id' => 'section par défaut',
            'student_status' => 'statut',
            'guardian1_first_name' => 'prénom du responsable 1',
            'guardian1_last_name' => 'nom du responsable 1',
            'guardian1_phone' => 'téléphone du responsable 1',
            'guardian1_email' => 'email du responsable 1',
            'guardian1_address' => 'adresse du responsable 1',
            'guardian1_relationship' => 'lien de parenté du responsable 1',
            'guardian2_first_name' => 'prénom du responsable 2',
            'guardian2_last_name' => 'nom du responsable 2',
            'guardian2_phone' => 'téléphone du responsable 2',
            'guardian2_email' => 'email du responsable 2',
            'guardian2_address' => 'adresse du responsable 2',
            'guardian2_relationship' => 'lien de parenté du responsable 2',
            'academic_year_id' => 'année académique',
            'level_section_id' => 'niveau/section',
        ];



        try {
            $validated = $request->validate($rules, $messages, $attributes);

            DB::beginTransaction();

            // 1. Créer l'étudiant
            $student = Student::create([
                'nisu' => $validated['nisu'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'section_id' => $validated['section_id'],
                'status' => $validated['student_status']
            ]);

            // 2. Créer le responsable 1
            $guardian1 = Guardian::create([
                'first_name' => $validated['guardian1_first_name'],
                'last_name' => $validated['guardian1_last_name'],
                'phone' => $validated['guardian1_phone'],
                'email' => $validated['guardian1_email'],
                'address' => $validated['guardian1_address'],
                'status' => 'active'
            ]);

            // 3. Créer le responsable 2
            $guardian2 = Guardian::create([
                'first_name' => $validated['guardian2_first_name'],
                'last_name' => $validated['guardian2_last_name'],
                'phone' => $validated['guardian2_phone'],
                'email' => $validated['guardian2_email'],
                'address' => $validated['guardian2_address'],
                'status' => 'active'
            ]);

            // 4. Attacher les deux responsables à l'étudiant
            $student->guardians()->attach([
                $guardian1->id => [
                    'relationship' => $validated['guardian1_relationship'],
                    'is_primary' => true  // Le premier est principal
                ],
                $guardian2->id => [
                    'relationship' => $validated['guardian2_relationship'],
                    'is_primary' => false
                ]
            ]);

            // 5. Créer l'inscription si demandée
            if (
                isset($validated['academic_year_id']) &&
                isset($validated['section_id']) &&
                is_numeric($validated['academic_year_id']) &&
                is_numeric($validated['section_id']) &&
                $validated['academic_year_id'] > 0 &&
                $validated['section_id'] > 0
            ) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $validated['academic_year_id'],
                    'level_section_id' => $validated['level_section_id'],
                    'status' => 'enrolled'
                ]);
            }

            DB::commit();

            return redirect()->route('students.show', $student)
                ->with('success', 'Étudiant et ses responsables créés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load([
            'guardians',
            'enrollments.academicYear',
            'enrollments.levelSection',
            'enrollments.grades.subject',
            'enrollments.payments.paymentType'
        ]);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nisu' => 'required|strsing|max:255|unique:students,nisu',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'status' => 'required|in:active,archived'

            // Note: On ne modifie pas les tuteurs ici
            // Il faudrait une interface séparée pour gérer les tuteurs
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Étudiant mis à jour avec succès.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Vérifier s'il y a des inscriptions actives
        $activeEnrollments = $student->enrollments()
            ->whereHas('academicYear', function ($query) {
                $query->where('status', 'active');
            })->count();

        if ($activeEnrollments > 0) {
            return redirect()->route('students.index')
                ->with('error', 'Impossible d\'archiver cet étudiant car il a des inscriptions en cours.');
        }

        // Archiver plutôt que supprimer définitivement
        $student->update(['status' => 'archived']);

        return redirect()->route('students.index')
            ->with('success', 'Étudiant archivé avec succès.');
    }

    public function getSectionsByLevel($levelId)
    {
        $sections = Section::where('level_id', $levelId)
            ->orderBy('level_id')
            ->orderBy('name')
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'level_name' => $section->level->name,
                    'full_name' => $section->name . ' (' . $section->level->name . ')'
                ];
            });

        return response()->json($sections);
    }

    /**
     * Mettre à jour les tuteurs d'un étudiant
     */
    public function updateGuardians(Request $request, Student $student)
    {
        $validated = $request->validate([
            'guardian_id_1' => 'required|exists:guardians,id|different:guardian_id_2',
            'guardian_id_2' => 'required|exists:guardians,id|different:guardian_id_1',
        ]);

        try {
            $guardianIds = [
                $validated['guardian_id_1'],
                $validated['guardian_id_2']
            ];

            $this->studentService->attachGuardians($student, $guardianIds);

            return redirect()->route('students.show', $student)
                ->with('success', 'Tuteurs mis à jour avec succès.');
        } catch (DomainException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['guardians' => $e->getMessage()]);
        }
    }

    public function getLevelSectionsBySection($sectionId)
    {
        $levelSections = LevelSection::with(['level', 'section'])
            // ->whereHas('subjects') // Optionnel: seulement ceux avec des matières
            ->where('section_id', $sectionId)
            ->get()
            ->map(function ($ls) {
                return [
                    'id' => $ls->id,
                    'name' => $ls->level->name . ' (' . $ls->section->name . ')',
                    'level_name' => $ls->level->name,
                    'section_name' => $ls->section->name,
                    'level_id' => $ls->level_id,
                    'section_id' => $ls->section_id
                ];
            });

        return response()->json($levelSections);
    }
}
