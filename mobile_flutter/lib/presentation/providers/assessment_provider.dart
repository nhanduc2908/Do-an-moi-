import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/assessment_model.dart';
import '../../data/repositories/assessment_repository.dart';

class AssessmentState {
  final List<AssessmentModel> assessments;
  final AssessmentModel? currentAssessment;
  final bool isLoading;
  final String? error;
  final bool hasMore;
  final int currentPage;

  AssessmentState({
    this.assessments = const [],
    this.currentAssessment,
    this.isLoading = false,
    this.error,
    this.hasMore = true,
    this.currentPage = 1,
  });

  AssessmentState copyWith({
    List<AssessmentModel>? assessments,
    AssessmentModel? currentAssessment,
    bool? isLoading,
    String? error,
    bool? hasMore,
    int? currentPage,
  }) {
    return AssessmentState(
      assessments: assessments ?? this.assessments,
      currentAssessment: currentAssessment ?? this.currentAssessment,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
      hasMore: hasMore ?? this.hasMore,
      currentPage: currentPage ?? this.currentPage,
    );
  }
}

class AssessmentNotifier extends StateNotifier<AssessmentState> {
  final AssessmentRepository _repository;

  AssessmentNotifier(this._repository) : super(AssessmentState());

  Future<void> loadAssessments({bool refresh = false}) async {
    if (state.isLoading) return;
    
    state = state.copyWith(isLoading: true, error: null);
    
    final page = refresh ? 1 : state.currentPage;
    final response = await _repository.getAssessments(page: page);
    
    if (response.isSuccess && response.data != null) {
      final newAssessments = refresh 
          ? response.data! 
          : [...state.assessments, ...response.data!];
      
      state = state.copyWith(
        assessments: newAssessments,
        isLoading: false,
        hasMore: response.data!.length == 20,
        currentPage: page + 1,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  Future<AssessmentModel?> loadAssessmentDetail(String id) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.getAssessmentDetail(id);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        currentAssessment: response.data,
        isLoading: false,
      );
      return response.data;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return null;
    }
  }

  Future<bool> createAssessment(Map<String, dynamic> data) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.createAssessment(data);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        assessments: [response.data!, ...state.assessments],
        isLoading: false,
      );
      return true;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return false;
    }
  }

  Future<bool> submitAssessment(String id, Map<String, dynamic> answers) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.submitAssessment(id, answers);
    
    state = state.copyWith(isLoading: false);
    
    if (response.isSuccess) {
      await loadAssessmentDetail(id);
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final assessmentProvider = StateNotifierProvider<AssessmentNotifier, AssessmentState>((ref) {
  final repository = AssessmentRepository();
  return AssessmentNotifier(repository);
});