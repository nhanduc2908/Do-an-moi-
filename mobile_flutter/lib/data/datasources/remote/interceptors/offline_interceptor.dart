import 'package:dio/dio.dart';
import '../../../../core/utils/logger.dart';
import '../../../../core/utils/network_checker.dart';
import '../../../models/sync_queue_model.dart';
import '../../local/sync_database.dart';

class OfflineInterceptor extends Interceptor {
  final SyncDatabase _syncDatabase = SyncDatabase();

  @override
  Future<void> onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    final isConnected = await NetworkChecker.isConnected();
    
    if (!isConnected && options.method != 'GET') {
      Logger.sync('Offline mode: Queueing request ${options.path}');
      
      final queueItem = SyncQueueModel(
        id: DateTime.now().millisecondsSinceEpoch.toString(),
        endpoint: options.path,
        method: options.method,
        data: options.data,
        headers: options.headers,
        createdAt: DateTime.now(),
        status: 'pending',
        retryCount: 0,
      );
      
      await _syncDatabase.insertSyncQueue(queueItem);
      
      return handler.resolve(
        Response(
          requestOptions: options,
          data: {
            'success': true,
            'message': 'Request queued for later sync',
            'queued': true,
          },
          statusCode: 202,
        ),
      );
    }
    
    return handler.next(options);
  }
}