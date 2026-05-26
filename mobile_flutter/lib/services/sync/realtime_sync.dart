import '../../data/datasources/remote/web_socket_service.dart';
import '../../data/repositories/sync_repository.dart';
import '../../core/utils/logger.dart';

class RealtimeSync {
  final WebSocketService _webSocketService;
  final SyncRepository _syncRepository;
  bool _isListening = false;

  RealtimeSync(this._webSocketService, this._syncRepository);

  void startListening() {
    if (_isListening) return;
    _webSocketService.subscribeToSync(_onSyncEvent);
    _isListening = true;
    Logger.sync('Realtime sync started');
  }

  void stopListening() {
    if (!_isListening) return;
    _webSocketService.unsubscribe('sync');
    _isListening = false;
  }

  void _onSyncEvent(dynamic data) {
    Logger.sync('Realtime sync event received');
    _syncRepository.syncToFlutter();
  }
}