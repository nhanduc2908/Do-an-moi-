import '../api_client.dart';
import '../../models/key_model.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class KeyApi {
  final ApiClient _apiClient;

  KeyApi(this._apiClient);

  Future<ApiResponseModel<List<KeyModel>>> getKeys({
    int page = 1,
    int limit = 20,
    String? status,
    String? type,
  }) async {
    final response = await _apiClient.get(
      ApiConstants.keys,
      queryParams: {
        'page': page,
        'limit': limit,
        if (status != null) 'status': status,
        if (type != null) 'type': type,
      },
    );
    
    if (response['success'] == true) {
      final List<dynamic> data = response['data']['items'] ?? [];
      final keys = data.map((item) => KeyModel.fromJson(item)).toList();
      return ApiResponseModel(
        success: true,
        data: keys,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load keys',
    );
  }

  Future<ApiResponseModel<KeyModel>> getKeyDetail(String id) async {
    final path = ApiConstants.keyDetail.replaceAll('{id}', id);
    final response = await _apiClient.get(path);
    
    if (response['success'] == true) {
      final key = KeyModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: key,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load key details',
    );
  }

  Future<ApiResponseModel<KeyModel>> generateKey(Map<String, dynamic> data) async {
    final response = await _apiClient.post(ApiConstants.keys, data: data);
    
    if (response['success'] == true) {
      final key = KeyModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: key,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to generate key',
    );
  }

  Future<ApiResponseModel<bool>> verifyKey(String keyId, String signature) async {
    final response = await _apiClient.post(ApiConstants.verifyKey, data: {
      'key_id': keyId,
      'signature': signature,
    });
    
    return ApiResponseModel(
      success: response['success'] == true,
      data: response['data']['valid'] ?? false,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> revokeKey(String id, String reason) async {
    final path = ApiConstants.revokeKey.replaceAll('{id}', id);
    final response = await _apiClient.post(path, data: {'reason': reason});
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<KeyModel>> rotateKey(String id) async {
    final path = ApiConstants.rotateKey.replaceAll('{id}', id);
    final response = await _apiClient.post(path);
    
    if (response['success'] == true) {
      final key = KeyModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: key,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to rotate key',
    );
  }

  Future<ApiResponseModel<List<dynamic>>> getKeyLogs(String id) async {
    final path = ApiConstants.keyLogs.replaceAll('{id}', id);
    final response = await _apiClient.get(path);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data']['items'] ?? [],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load key logs',
    );
  }
}