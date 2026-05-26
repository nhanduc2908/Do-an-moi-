import '../api_client.dart';
import '../../models/incident_model.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class IncidentApi {
  final ApiClient _apiClient;

  IncidentApi(this._apiClient);

  Future<ApiResponseModel<List<IncidentModel>>> getIncidents({
    int page = 1,
    int limit = 20,
    String? severity,
    String? status,
    String? category,
  }) async {
    final response = await _apiClient.get(
      ApiConstants.incidents,
      queryParams: {
        'page': page,
        'limit': limit,
        if (severity != null) 'severity': severity,
        if (status != null) 'status': status,
        if (category != null) 'category': category,
      },
    );
    
    if (response['success'] == true) {
      final List<dynamic> data = response['data']['items'] ?? [];
      final incidents = data.map((item) => IncidentModel.fromJson(item)).toList();
      return ApiResponseModel(
        success: true,
        data: incidents,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load incidents',
    );
  }

  Future<ApiResponseModel<IncidentModel>> getIncidentDetail(String id) async {
    final path = ApiConstants.incidentDetail.replaceAll('{id}', id);
    final response = await _apiClient.get(path);
    
    if (response['success'] == true) {
      final incident = IncidentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: incident,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load incident details',
    );
  }

  Future<ApiResponseModel<IncidentModel>> createIncident(Map<String, dynamic> data) async {
    final response = await _apiClient.post(ApiConstants.incidents, data: data);
    
    if (response['success'] == true) {
      final incident = IncidentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: incident,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to create incident',
    );
  }

  Future<ApiResponseModel<IncidentModel>> updateIncident(String id, Map<String, dynamic> data) async {
    final path = ApiConstants.incidentDetail.replaceAll('{id}', id);
    final response = await _apiClient.put(path, data: data);
    
    if (response['success'] == true) {
      final incident = IncidentModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: incident,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to update incident',
    );
  }

  Future<ApiResponseModel<void>> resolveIncident(String id, Map<String, dynamic> resolution) async {
    final path = ApiConstants.resolveIncident.replaceAll('{id}', id);
    final response = await _apiClient.post(path, data: resolution);
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> addComment(String id, String comment) async {
    final path = ApiConstants.addComment.replaceAll('{id}', id);
    final response = await _apiClient.post(path, data: {'comment': comment});
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<dynamic>> getIncidentStats() async {
    final response = await _apiClient.get(ApiConstants.incidentStats);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load statistics',
    );
  }

  Future<ApiResponseModel<void>> assignIncident(String id, String userId) async {
    final path = ApiConstants.incidentDetail.replaceAll('{id}', id);
    final response = await _apiClient.patch(path, data: {'assigned_to': userId});
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> escalateIncident(String id, String reason) async {
    final path = ApiConstants.incidentDetail.replaceAll('{id}', id);
    final response = await _apiClient.post('$path/escalate', data: {'reason': reason});
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }
}