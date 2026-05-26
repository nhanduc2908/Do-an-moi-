// Đường dẫn: mobile_flutter/test/unit/network_checker_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/core/utils/network_checker.dart';
import 'package:connectivity_plus/connectivity_plus.dart';

void main() {
  group('NetworkChecker Tests', () {
    test('isConnected returns boolean', () async {
      final result = await NetworkChecker.isConnected();
      
      expect(result, isBool);
    });

    test('getConnectionType returns ConnectivityResult', () async {
      final result = await NetworkChecker.getConnectionType();
      
      expect(result, isA<ConnectivityResult>());
    });

    test('isWifi returns true for wifi', () {
      final result = NetworkChecker.isWifi(ConnectivityResult.wifi);
      
      expect(result, true);
    });

    test('isWifi returns false for mobile', () {
      final result = NetworkChecker.isWifi(ConnectivityResult.mobile);
      
      expect(result, false);
    });

    test('isMobile returns true for mobile', () {
      final result = NetworkChecker.isMobile(ConnectivityResult.mobile);
      
      expect(result, true);
    });

    test('getConnectionName returns correct name', () {
      expect(NetworkChecker.getConnectionName(ConnectivityResult.wifi), 'Wi-Fi');
      expect(NetworkChecker.getConnectionName(ConnectivityResult.mobile), 'Mobile Data');
      expect(NetworkChecker.getConnectionName(ConnectivityResult.none), 'Offline');
    });
  });
}