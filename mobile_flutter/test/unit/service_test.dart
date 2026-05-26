// Đường dẫn: mobile_flutter/test/unit/service_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/services/auth/mfa_service.dart';
import 'package:security_evaluation_app/services/auth/biometric_auth.dart';

void main() {
  group('MfaService Tests', () {
    late MfaService mfaService;

    setUp(() {
      mfaService = MfaService();
    });

    test('enableMfa stores secret', () async {
      await mfaService.enableMfa('test_secret');
      final secret = await mfaService.getMfaSecret();
      
      expect(secret, 'test_secret');
    });

    test('disableMfa removes secret', () async {
      await mfaService.enableMfa('test_secret');
      await mfaService.disableMfa();
      final secret = await mfaService.getMfaSecret();
      
      expect(secret, null);
    });

    test('isMfaEnabled returns true when enabled', () async {
      await mfaService.enableMfa('test_secret');
      final enabled = await mfaService.isMfaEnabled();
      
      expect(enabled, true);
    });

    test('generateOtp returns 6-digit code', () {
      final otp = mfaService.generateOtp('test_secret');
      
      expect(otp.length, 6);
      expect(int.tryParse(otp), isNotNull);
    });
  });

  group('BiometricAuth Tests', () {
    test('isAvailable returns boolean', () async {
      final available = await BiometricAuth.isAvailable();
      
      expect(available, isBool);
    });
  });
}