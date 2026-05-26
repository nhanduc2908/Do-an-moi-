import 'package:connectivity_plus/connectivity_plus.dart';
import 'logger.dart';

class NetworkChecker {
  static final Connectivity _connectivity = Connectivity();
  
  static Future<bool> isConnected() async {
    try {
      final result = await _connectivity.checkConnectivity();
      return result != ConnectivityResult.none;
    } catch (e) {
      Logger.error('Network check failed', e);
      return false;
    }
  }
  
  static Future<ConnectivityResult> getConnectionType() async {
    try {
      return await _connectivity.checkConnectivity();
    } catch (e) {
      return ConnectivityResult.none;
    }
  }
  
  static Stream<ConnectivityResult> get onConnectivityChanged => _connectivity.onConnectivityChanged;
  
  static bool isWifi(ConnectivityResult result) => result == ConnectivityResult.wifi;
  static bool isMobile(ConnectivityResult result) => result == ConnectivityResult.mobile;
  
  static String getConnectionName(ConnectivityResult result) {
    switch (result) {
      case ConnectivityResult.wifi: return 'Wi-Fi';
      case ConnectivityResult.mobile: return 'Mobile Data';
      case ConnectivityResult.ethernet: return 'Ethernet';
      case ConnectivityResult.none: return 'Offline';
      default: return 'Unknown';
    }
  }
}