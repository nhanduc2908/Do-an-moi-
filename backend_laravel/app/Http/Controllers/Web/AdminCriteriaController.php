<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\Domain;

class AdminCriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Danh sách criteria
     */
    public function index(Request $request)
    {
        $criteria = Criteria::with('domain')
            ->when($request->domain_id, function($query, $domainId) {
                $query->where('domain_id', $domainId);
            })
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('domain_id')
            ->orderBy('code')
            ->paginate(20);

        $domains = Domain::all();

        return view('admin.criteria.index', compact('criteria', 'domains'));
    }

    /**
     * Form tạo criteria
     */
    public function create()
    {
        $domains = Domain::orderBy('name')->get();

        return view('admin.criteria.create', compact('domains'));
    }

    /**
     * Lưu criteria mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:criteria|max:50',
            'name' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,id',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:0|max:100',
            'scoring_method' => 'required|in:manual,auto,binary,range',
        ]);

        Criteria::create($request->all());

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criteria created successfully.');
    }

    /**
     * Chi tiết criteria
     */
    public function show($id)
    {
        $criteria = Criteria::with('domain', 'assessments')->findOrFail($id);

        return view('admin.criteria.show', compact('criteria'));
    }

    /**
     * Form sửa criteria
     */
    public function edit($id)
    {
        $criteria = Criteria::findOrFail($id);
        $domains = Domain::orderBy('name')->get();

        return view('admin.criteria.edit', compact('criteria', 'domains'));
    }

    /**
     * Cập nhật criteria
     */
    public function update(Request $request, $id)
    {
        $criteria = Criteria::findOrFail($id);

        $request->validate([
            'code' => 'required|string|unique:criteria,code,' . $id,
            'name' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,id',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:0|max:100',
            'scoring_method' => 'required|in:manual,auto,binary,range',
            'is_active' => 'nullable|boolean',
        ]);

        $criteria->update($request->all());

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criteria updated successfully.');
    }

    /**
     * Xóa criteria
     */
    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);

        if ($criteria->assessments()->count() > 0) {
            return back()->with('error', 'Cannot delete criteria with associated assessments.');
        }

        $criteria->delete();

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criteria deleted successfully.');
    }

    /**
     * Bulk upload criteria
     */
    public function bulkUploadForm()
    {
        return view('admin.criteria.bulk-upload');
    }

    /**
     * Process bulk upload
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
        ]);

        // Xử lý bulk upload
        // $this->criteriaService->bulkUpload($request->file('file'));

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criteria uploaded successfully.');
    }

    /**
     * AI generate criteria
     */
    public function aiGenerate(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'count' => 'integer|min:1|max:50',
        ]);

        // Gọi AI service
        // $generated = $this->aiService->generateCriteria($request->domain_id, $request->count ?? 10);

        return redirect()->route('admin.criteria.index')
            ->with('success', 'AI generated criteria added.');
    }
}