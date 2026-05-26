import 'dart:async';
import '../../core/utils/network_checker.dart';
import '../../core/utils/logger.dart';

class OfflineSyncTrigger {
  final Function _onOnline;
  Timer? _checkTimer;
  bool _wasOnline = false;

  OfflineSyncTrigger(this._onOnline);

  void start() {
    _checkTimer = Timer.periodic(const Duration(seconds: 10), (timer) async {
      final isOnline = await NetworkChecker.isConnected();
      
      if (!_wasOnline && isOnline) {
        Logger.offline('Network restored, triggering sync');
        _onOnline();
      }
      
      _wasOnline = isOnline;
    });
  }

  void stop() {
    _checkTimer?.cancel();
  }
}