// Đường dẫn: mobile_flutter/test/mocks/mock_network_info.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:connectivity_plus/connectivity_plus.dart';

@GenerateMocks([Connectivity])

class MockNetworkInfo {
  static MockConnectivity createMockConnectivity() {
    final mock = MockConnectivity();
    
    when(mock.checkConnectivity()).thenAnswer((_) async => ConnectivityResult.wifi);
    when(mock.onConnectivityChanged).thenAnswer((_) => Stream.value(ConnectivityResult.wifi));
    
    return mock;
  }

  static MockConnectivity createMockOfflineConnectivity() {
    final mock = MockConnectivity();
    
    when(mock.checkConnectivity()).thenAnswer((_) async => ConnectivityResult.none);
    when(mock.onConnectivityChanged).thenAnswer((_) => Stream.value(ConnectivityResult.none));
    
    return mock;
  }
}