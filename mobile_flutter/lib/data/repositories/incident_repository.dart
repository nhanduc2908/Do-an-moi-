import '../models/incident_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/incident_api.dart';
import '../../core/utils/logger.dart';

class IncidentRepository {
  final IncidentApi _incidentApi;

  IncidentRepository(this._incidentApi);

  Future<ApiResponseModel<List<IncidentModel>>> getIncidents({
    int page = 1,
    int limit = 20,
    String? severity,
    String? status,
    String? category,
  }) async {
    try {
      return await _incidentApi.getIncidents(
        page: page,
        limit: limit,
        severity: severity,
        status: status,
        category: category,
      );
    } catch (e) {
      Logger.error('Get incidents error', e);
      return ApiResponseModel(success: false, message: 'Failed to load incidents');
    }
  }

  Future<ApiResponseModel<IncidentModel>> getIncidentDetail(String id) async {
    try {
      return await _incidentApi.getIncidentDetail(id);
    } catch (e) {
      Logger.error('Get incident detail error', e);
      return ApiResponseModel(success: false, message: 'Failed to load incident details');
    }
  }

  Future<ApiResponseModel<IncidentModel>> createIncident(Map<String, dynamic> data) async {
    try {
      return await _incidentApi.createIncident(data);
    } catch (e) {
      Logger.error('Create incident error', e);
      return ApiResponseModel(success: false, message: 'Failed to create incident');
    }
  }

  Future<ApiResponseModel<IncidentModel>> updateIncident(String id, Map<String, dynamic> data) async {
    try {
      return await _incidentApi.updateIncident(id, data);
    } catch (e) {
      Logger.error('Update incident error', e);
      return ApiResponseModel(success: false, message: 'Failed to update incident');
    }
  }

  Future<ApiResponseModel<void>> resolveIncident(String id, Map<String, dynamic> resolution) async {
    try {
      return await _incidentApi.resolveIncident(id, resolution);
    } catch (e) {
      Logger.error('Resolve incident error', e);
      return ApiResponseModel(success: false, message: 'Failed to resolve incident');
    }
  }

  Future<ApiResponseModel<void>> addComment(String id, String comment) async {
    try {
      return await _incidentApi.addComment(id, comment);
    } catch (e) {
      Logger.error('Add comment error', e);
      return ApiResponseModel(success: false, message: 'Failed to add comment');
    }
  }

  Future<ApiResponseModel<dynamic>> getIncidentStats() async {
    try {
      return await _incidentApi.getIncidentStats();
    } catch (e) {
      Logger.error('Get incident stats error', e);
      return ApiResponseModel(success: false, message: 'Failed to load statistics');
    }
  }

  Future<ApiResponseModel<void>> assignIncident(String id, String userId) async {
    try {
      return await _incidentApi.assignIncident(id, userId);
    } catch (e) {
      Logger.error('Assign incident error', e);
      return ApiResponseModel(success: false, message: 'Failed to assign incident');
    }
  }

  Future<ApiResponseModel<void>> escalateIncident(String id, String reason) async {
    try {
      return await _incidentApi.escalateIncident(id, reason);
    } catch (e) {
      Logger.error('Escalate incident error', e);
      return ApiResponseModel(success: false, message: 'Failed to escalate incident');
    }
  }
}