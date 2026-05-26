import 'package:flutter_background_service/flutter_background_service.dart';
import 'sync_engine.dart';
import '../../core/utils/logger.dart';

class BackgroundSyncService {
  static final BackgroundSyncService _instance = BackgroundSyncService._internal();
  factory BackgroundSyncService() => _instance;
  BackgroundSyncService._internal();

  Future<void> initialize(SyncEngine syncEngine) async {
    final service = FlutterBackgroundService();
    await service.configure(
      androidConfiguration: AndroidConfiguration(
        onStart: (service) => _onStart(service, syncEngine),
        autoStart: true,
        isForegroundMode: true,
        notificationTitle: 'Security Platform',
        notificationText: 'Syncing data...',
      ),
      iosConfiguration: IosConfiguration(onStart: (service) => _onStart(service, syncEngine)),
    );
  }

  @pragma('vm:entry-point')
  void _onStart(ServiceInstance service, SyncEngine syncEngine) {
    DartPluginRegistrant.ensureInitialized();
    Timer.periodic(const Duration(minutes: 30), (timer) async {
      await syncEngine.sync();
    });
  }

  Future<void> start() async {
    await FlutterBackgroundService().start();
    Logger.sync('Background sync started');
  }
}