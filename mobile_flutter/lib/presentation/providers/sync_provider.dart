import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../services/sync/sync_engine.dart';
import '../../services/sync/sync_monitor.dart';

class SyncState {
  final bool isSyncing;
  final DateTime? lastSyncTime;
  final int pendingItems;
  final String? error;

  SyncState({
    this.isSyncing = false,
    this.lastSyncTime,
    this.pendingItems = 0,
    this.error,
  });

  SyncState copyWith({
    bool? isSyncing,
    DateTime? lastSyncTime,
    int? pendingItems,
    String? error,
  }) {
    return SyncState(
      isSyncing: isSyncing ?? this.isSyncing,
      lastSyncTime: lastSyncTime ?? this.lastSyncTime,
      pendingItems: pendingItems ?? this.pendingItems,
      error: error ?? this.error,
    );
  }
}

class SyncNotifier extends StateNotifier<SyncState> {
  final SyncEngine _syncEngine;
  final SyncMonitor _syncMonitor;

  SyncNotifier(this._syncEngine, this._syncMonitor) : super(SyncState()) {
    _listenToMonitor();
    _syncEngine.startAutoSync();
    _syncMonitor.startMonitoring();
  }

  void _listenToMonitor() {
    _syncMonitor.statusStream.listen((status) {
      state = state.copyWith(
        isSyncing: status.isSyncing,
        lastSyncTime: status.lastSyncTime,
        pendingItems: status.pendingItems,
      );
    });
  }

  Future<void> syncNow() async {
    state = state.copyWith(isSyncing: true, error: null);
    
    try {
      await _syncEngine.sync();
      state = state.copyWith(isSyncing: false);
    } catch (e) {
      state = state.copyWith(
        isSyncing: false,
        error: e.toString(),
      );
    }
  }

  void clearError() {
    state = state.copyWith(error: null);
  }

  void dispose() {
    _syncEngine.stopAutoSync();
    _syncMonitor.stopMonitoring();
    super.dispose();
  }
}

final syncProvider = StateNotifierProvider<SyncNotifier, SyncState>((ref) {
  // Note: Need to properly initialize dependencies
  final syncEngine = SyncEngine();
  final syncMonitor = SyncMonitor();
  return SyncNotifier(syncEngine, syncMonitor);
});