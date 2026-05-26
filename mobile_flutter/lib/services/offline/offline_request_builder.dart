import '../../data/models/sync_queue_model.dart';
import '../../core/utils/logger.dart';

class OfflineRequestBuilder {
  SyncQueueModel buildRequest({
    required String endpoint,
    required String method,
    dynamic data,
    Map<String, dynamic>? headers,
  }) {
    return SyncQueueModel(
      id: DateTime.now().millisecondsSinceEpoch.toString(),
      endpoint: endpoint,
      method: method,
      data: data,
      headers: headers,
      createdAt: DateTime.now(),
      status: 'pending',
      retryCount: 0,
    );
  }

  SyncQueueModel buildPostRequest(String endpoint, dynamic data) {
    return buildRequest(endpoint: endpoint, method: 'POST', data: data);
  }

  SyncQueueModel buildPutRequest(String endpoint, dynamic data) {
    return buildRequest(endpoint: endpoint, method: 'PUT', data: data);
  }

  SyncQueueModel buildDeleteRequest(String endpoint) {
    return buildRequest(endpoint: endpoint, method: 'DELETE');
  }
}