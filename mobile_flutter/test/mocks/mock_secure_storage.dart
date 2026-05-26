// Đường dẫn: mobile_flutter/test/mocks/mock_secure_storage.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

@GenerateMocks([FlutterSecureStorage])

class MockSecureStorage {
  static const String testToken = 'test_access_token';
  static const String testRefreshToken = 'test_refresh_token';

  static MockFlutterSecureStorage create() {
    final mock = MockFlutterSecureStorage();
    
    when(mock.read(key: anyNamed('key'))).thenAnswer((invocation) async {
      final key = invocation.namedArguments[#key];
      if (key == 'access_token') return testToken;
      if (key == 'refresh_token') return testRefreshToken;
      return null;
    });
    
    when(mock.write(key: anyNamed('key'), value: anyNamed('value')))
        .thenAnswer((_) async {});
    
    when(mock.delete(key: anyNamed('key'))).thenAnswer((_) async {});
    when(mock.deleteAll()).thenAnswer((_) async {});
    
    return mock;
  }
}