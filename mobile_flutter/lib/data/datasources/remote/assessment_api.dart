import '../api_client.dart';
import '../../models/assessment_model.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class AssessmentApi {
  final ApiClient _apiClient;

  AssessmentApi(this._apiClient);

  Future<ApiResponseModel<List<AssessmentModel>>> getAssessments({
    int page = 1,
    int limit = 20,
    String? status,
    String? type,
  }) async {
    final response = await _apiClient.get(
      ApiConstants.assessments,
      queryParams: {
        'page': page,
        'limit': limit,
        if (status != null) 'status': status,
        if (type != null) 'type': type,
      },
    );
    
    if (response['success'] == true) {
      final List<dynamic> data = response['data']['items'] ?? [];
      final assessments = data.map((item) => AssessmentModel.fromJson(item)).toList();
      return ApiResponseModel(
        success: true,
        data: assessments,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load assessments',
    );
  }

  Future<ApiResponseModel<AssessmentModel>> getAssessmentDetail(String id) async {
    final path = ApiConstants.assessmentDetail.replaceAll('{id}', id);
    final response = await _apiClient.get(path);
    
    if (response['success'] == true) {
      final assessment = AssessmentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: assessment,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load assessment details',
    );
  }

  Future<ApiResponseModel<AssessmentModel>> createAssessment(Map<String, dynamic> data) async {
    final response = await _apiClient.post(ApiConstants.assessments, data: data);
    
    if (response['success'] == true) {
      final assessment = AssessmentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: assessment,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to create assessment',
    );
  }

  Future<ApiResponseModel<AssessmentModel>> updateAssessment(String id, Map<String, dynamic> data) async {
    final path = ApiConstants.assessmentDetail.replaceAll('{id}', id);
    final response = await _apiClient.put(path, data: data);
    
    if (response['success'] == true) {
      final assessment = AssessmentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: assessment,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to update assessment',
    );
  }

  Future<ApiResponseModel<void>> submitAssessment(String id, Map<String, dynamic> answers) async {
    final path = ApiConstants.submitAssessment.replaceAll('{id}', id);
    final response = await _apiClient.post(path, data: answers);
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<AssessmentModel>> reviewAssessment(String id, Map<String, dynamic> review) async {
    final path = ApiConstants.reviewAssessment.replaceAll('{id}', id);
    final response = await _apiClient.post(path, data: review);
    
    if (response['success'] == true) {
      final assessment = AssessmentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: assessment,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to review assessment',
    );
  }

  Future<ApiResponseModel<dynamic>> getAssessmentProgress(String id) async {
    final path = ApiConstants.assessmentProgress.replaceAll('{id}', id);
    final response = await _apiClient.get(path);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load progress',
    );
  }

  Future<ApiResponseModel<String>> exportAssessment(String id, String format) async {
    final path = ApiConstants.exportAssessment.replaceAll('{id}', id);
    final response = await _apiClient.get(path, queryParams: {'format': format});
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data']['url'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to export assessment',
    );
  }
}