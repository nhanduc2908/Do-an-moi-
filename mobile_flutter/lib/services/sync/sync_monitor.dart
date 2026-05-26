import 'dart:async';
import '../../data/repositories/sync_repository.dart';
import '../../core/utils/logger.dart';

class SyncMonitor {
  final SyncRepository _syncRepository;
  Timer? _monitorTimer;
  final _statusController = StreamController<SyncStatus>.broadcast();

  SyncMonitor(this._syncRepository);

  Stream<SyncStatus> get statusStream => _statusController.stream;

  void startMonitoring({Duration interval = const Duration(seconds: 30)}) {
    _monitorTimer = Timer.periodic(interval, (timer) async {
      await _checkStatus();
    });
  }

  void stopMonitoring() {
    _monitorTimer?.cancel();
    _statusController.close();
  }

  Future<void> _checkStatus() async {
    final response = await _syncRepository.getSyncStatus();
    if (response.isSuccess && response.data != null) {
      _statusController.add(SyncStatus.fromJson(response.data));
    }
  }
}

class SyncStatus {
  final bool isSyncing;
  final DateTime? lastSyncTime;
  final int pendingItems;

  SyncStatus({required this.isSyncing, this.lastSyncTime, required this.pendingItems});

  factory SyncStatus.fromJson(Map<String, dynamic> json) {
    return SyncStatus(
      isSyncing: json['is_syncing'] ?? false,
      lastSyncTime: json['last_sync_time'] != null ? DateTime.parse(json['last_sync_time']) : null,
      pendingItems: json['pending_items'] ?? 0,
    );
  }
}