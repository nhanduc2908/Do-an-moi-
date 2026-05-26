// Đường dẫn: mobile_flutter/test/helpers/mock_factories.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/data/datasources/remote/api_client.dart';
import 'package:security_evaluation_app/data/datasources/local/local_storage.dart';
import 'package:security_evaluation_app/data/repositories/auth_repository.dart';
import 'package:security_evaluation_app/services/auth/token_manager.dart';

@GenerateMocks([
  ApiClient,
  LocalStorage,
  AuthRepository,
  TokenManager,
])

class MockFactories {
  static ApiClient createMockApiClient() {
    final mock = MockApiClient();
    when(mock.get(any)).thenAnswer((_) async => {'success': true, 'data': {}});
    when(mock.post(any)).thenAnswer((_) async => {'success': true, 'data': {}});
    return mock;
  }

  static LocalStorage createMockLocalStorage() {
    final mock = MockLocalStorage();
    when(mock.read(any)).thenAnswer((_) async => null);
    when(mock.write(any, any)).thenAnswer((_) async => {});
    return mock;
  }

  static AuthRepository createMockAuthRepository() {
    final mock = MockAuthRepository();
    when(mock.login(any, any)).thenAnswer((_) async => 
      ApiResponseModel(success: true, data: TestData.testUser));
    return mock;
  }

  static TokenManager createMockTokenManager() {
    final mock = MockTokenManager();
    when(mock.getAccessToken()).thenAnswer((_) async => TestData.testToken);
    return mock;
  }
}