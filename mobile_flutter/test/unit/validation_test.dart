// Đường dẫn: mobile_flutter/test/unit/validation_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/core/utils/validators.dart';

void main() {
  group('Email Validation Tests', () {
    test('validateEmail returns null for valid email', () {
      expect(Validators.validateEmail('test@example.com'), null);
      expect(Validators.validateEmail('user.name@domain.co.uk'), null);
    });

    test('validateEmail returns error for invalid email', () {
      expect(Validators.validateEmail('invalid'), isNotNull);
      expect(Validators.validateEmail('test@'), isNotNull);
      expect(Validators.validateEmail(''), isNotNull);
      expect(Validators.validateEmail(null), isNotNull);
    });
  });

  group('Password Validation Tests', () {
    test('validatePassword returns null for valid password', () {
      expect(Validators.validatePassword('Test@123456'), null);
    });

    test('validatePassword returns error for short password', () {
      expect(Validators.validatePassword('Test@1'), isNotNull);
    });

    test('validatePassword returns error for no uppercase', () {
      expect(Validators.validatePassword('test@123456'), isNotNull);
    });

    test('validatePassword returns error for no lowercase', () {
      expect(Validators.validatePassword('TEST@123456'), isNotNull);
    });

    test('validatePassword returns error for no number', () {
      expect(Validators.validatePassword('Test@abcdef'), isNotNull);
    });

    test('validatePassword returns error for no special character', () {
      expect(Validators.validatePassword('Test123456'), isNotNull);
    });
  });

  group('Confirm Password Validation Tests', () {
    test('validateConfirmPassword returns null for matching passwords', () {
      expect(Validators.validateConfirmPassword('password', 'password'), null);
    });

    test('validateConfirmPassword returns error for non-matching passwords', () {
      expect(Validators.validateConfirmPassword('password', 'different'), isNotNull);
    });
  });

  group('Required Field Validation Tests', () {
    test('validateRequired returns null for non-empty value', () {
      expect(Validators.validateRequired('value', 'Field'), null);
    });

    test('validateRequired returns error for empty value', () {
      expect(Validators.validateRequired('', 'Field'), isNotNull);
      expect(Validators.validateRequired(null, 'Field'), isNotNull);
    });
  });

  group('Strong Password Check Tests', () {
    test('isStrongPassword returns true for strong password', () {
      expect(Validators.isStrongPassword('Test@123456789'), true);
    });

    test('isStrongPassword returns false for weak password', () {
      expect(Validators.isStrongPassword('weak'), false);
    });
  });
}