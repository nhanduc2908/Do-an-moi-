// Đường dẫn: mobile_flutter/test/unit/token_manager_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/services/auth/token_manager.dart';
import 'package:security_evaluation_app/core/utils/secure_storage.dart';

void main() {
  late TokenManager tokenManager;

  setUp(() async {
    await SecureStorage.init();
    tokenManager = TokenManager();
    await tokenManager.clearTokens();
  });

  tearDown(() async {
    await tokenManager.clearTokens();
  });

  group('TokenManager Tests', () {
    test('saveTokens stores tokens correctly', () async {
      await tokenManager.saveTokens('access_token_123', 'refresh_token_456');
      
      final accessToken = await tokenManager.getAccessToken();
      final refreshToken = await tokenManager.getRefreshToken();
      
      expect(accessToken, 'access_token_123');
      expect(refreshToken, 'refresh_token_456');
    });

    test('clearTokens removes all tokens', () async {
      await tokenManager.saveTokens('access_token_123', 'refresh_token_456');
      await tokenManager.clearTokens();
      
      final accessToken = await tokenManager.getAccessToken();
      final refreshToken = await tokenManager.getRefreshToken();
      
      expect(accessToken, null);
      expect(refreshToken, null);
    });

    test('hasValidToken returns true when token exists', () async {
      await tokenManager.saveTokens('access_token_123', 'refresh_token_456');
      
      final hasToken = await tokenManager.hasValidToken();
      
      expect(hasToken, true);
    });

    test('hasValidToken returns false when no token', () async {
      final hasToken = await tokenManager.hasValidToken();
      
      expect(hasToken, false);
    });
  });
}