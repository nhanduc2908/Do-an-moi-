// Đường dẫn: mobile_flutter/test/unit/encryption_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/core/utils/encryption_helper.dart';

void main() {
  group('EncryptionHelper Tests', () {
    const testString = 'Sensitive data to encrypt';
    
    test('encrypt and decrypt returns original string', () {
      final encrypted = EncryptionHelper.encrypt(testString);
      final decrypted = EncryptionHelper.decrypt(encrypted);
      
      expect(decrypted, testString);
    });

    test('encrypt returns different results for same input', () {
      final encrypted1 = EncryptionHelper.encrypt(testString);
      final encrypted2 = EncryptionHelper.encrypt(testString);
      
      expect(encrypted1, isNot(equals(encrypted2)));
    });

    test('hashSha256 returns consistent hash', () {
      const input = 'test';
      final hash1 = EncryptionHelper.hashSha256(input);
      final hash2 = EncryptionHelper.hashSha256(input);
      
      expect(hash1, hash2);
    });

    test('hashSha256 returns different hash for different inputs', () {
      const input1 = 'test1';
      const input2 = 'test2';
      final hash1 = EncryptionHelper.hashSha256(input1);
      final hash2 = EncryptionHelper.hashSha256(input2);
      
      expect(hash1, isNot(equals(hash2)));
    });

    test('generateRandomKey returns string of correct length', () {
      final key = EncryptionHelper.generateRandomKey(32);
      final decoded = base64.decode(key);
      
      expect(decoded.length, 32);
    });

    test('generateRandomBytes returns bytes of correct length', () {
      final bytes = EncryptionHelper.generateRandomBytes(16);
      
      expect(bytes.length, 16);
    });
  });
}