import '../api_client.dart';
import '../../models/sync_queue_model.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class SyncApi {
  final ApiClient _apiClient;

  SyncApi(this._apiClient);

  Future<ApiResponseModel<void>> syncToFlutter({
    String? lastSyncTime,
    List<String>? entityTypes,
  }) async {
    final response = await _apiClient.post(
      ApiConstants.syncFlutter,
      data: {
        if (lastSyncTime != null) 'last_sync_time': lastSyncTime,
        if (entityTypes != null) 'entity_types': entityTypes,
      },
    );
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> syncToFirebase(Map<String, dynamic> data) async {
    final response = await _apiClient.post(ApiConstants.syncFirebase, data: data);
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<dynamic>> getSyncStatus() async {
    final response = await _apiClient.get(ApiConstants.syncStatus);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to get sync status',
    );
  }

  Future<ApiResponseModel<List<SyncQueueModel>>> getSyncLogs({
    int page = 1,
    int limit = 20,
    String? status,
    String? type,
  }) async {
    final response = await _apiClient.get(
      ApiConstants.syncLogs,
      queryParams: {
        'page': page,
        'limit': limit,
        if (status != null) 'status': status,
        if (type != null) 'type': type,
      },
    );
    
    if (response['success'] == true) {
      final List<dynamic> data = response['data']['items'] ?? [];
      final logs = data.map((item) => SyncQueueModel.fromJson(item)).toList();
      return ApiResponseModel(
        success: true,
        data: logs,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to get sync logs',
    );
  }
}