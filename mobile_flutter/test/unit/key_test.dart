// Đường dẫn: mobile_flutter/test/unit/key_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/data/models/key_model.dart';

void main() {
  group('KeyModel Tests', () {
    test('KeyModel fromJson creates correct object', () {
      final json = {
        'id': '1',
        'key_id': 'KEY-001',
        'type': 'AES',
        'size': 256,
        'purpose': 'encryption',
        'status': 'active',
        'fingerprint': 'AB:CD:EF:12:34:56:78:90',
      };
      
      final key = KeyModel.fromJson(json);
      
      expect(key.id, '1');
      expect(key.keyId, 'KEY-001');
      expect(key.type, 'AES');
      expect(key.size, 256);
      expect(key.purpose, 'encryption');
      expect(key.status, 'active');
      expect(key.fingerprint, 'AB:CD:EF:12:34:56:78:90');
    });

    test('KeyModel isActive returns true for active status', () {
      final key = KeyModel(status: 'active');
      expect(key.isActive, true);
    });

    test('KeyModel isRevoked returns true for revoked status', () {
      final key = KeyModel(status: 'revoked');
      expect(key.isRevoked, true);
    });

    test('KeyModel isExpired returns true when expires_at is in past', () {
      final key = KeyModel(expiresAt: DateTime.now().subtract(const Duration(days: 1)));
      expect(key.isExpired, true);
    });

    test('KeyModel isExpired returns false when expires_at is in future', () {
      final key = KeyModel(expiresAt: DateTime.now().add(const Duration(days: 1)));
      expect(key.isExpired, false);
    });

    test('KeyModel statusDisplay returns correct text', () {
      final active = KeyModel(status: 'active');
      expect(active.statusDisplay, 'Active');
      
      final revoked = KeyModel(status: 'revoked');
      expect(revoked.statusDisplay, 'Revoked');
      
      final expired = KeyModel(expiresAt: DateTime.now().subtract(const Duration(days: 1)));
      expect(expired.statusDisplay, 'Expired');
    });
  });
}