import 'dart:async';
import 'package:workmanager/workmanager.dart';
import 'sync_engine.dart';
import '../../core/utils/logger.dart';

class SyncScheduler {
  static const String syncTask = 'syncTask';
  final SyncEngine _syncEngine;

  SyncScheduler(this._syncEngine);

  Future<void> initialize() async {
    await Workmanager().initialize(callbackDispatcher, isInDebugMode: true);
    Logger.sync('SyncScheduler initialized');
  }

  void schedulePeriodicSync({Duration frequency = const Duration(minutes: 15)}) {
    Workmanager().registerPeriodicTask(syncTask, syncTask, frequency: frequency);
    Logger.sync('Periodic sync scheduled');
  }

  void scheduleOneTimeSync({Duration delay = const Duration(seconds: 10)}) {
    Workmanager().registerOneOffTask(syncTask, syncTask, initialDelay: delay);
    Logger.sync('One-time sync scheduled');
  }

  void cancelAll() {
    Workmanager().cancelAll();
    Logger.sync('All syncs cancelled');
  }
}

@pragma('vm:entry-point')
void callbackDispatcher() {
  Workmanager().executeTask((task, inputData) async {
    Logger.sync('Background sync executed');
    return Future.value(true);
  });
}