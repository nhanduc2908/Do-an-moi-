import 'dart:convert';
import '../../core/utils/secure_storage.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/logger.dart';

class MfaService {
  Future<void> enableMfa(String secret) async {
    await SecureStorage.write(StorageKeys.mfaSecret, secret);
    Logger.auth('MFA enabled');
  }

  Future<void> disableMfa() async {
    await SecureStorage.delete(StorageKeys.mfaSecret);
    Logger.auth('MFA disabled');
  }

  Future<bool> isMfaEnabled() async {
    final secret = await SecureStorage.read(StorageKeys.mfaSecret);
    return secret != null;
  }

  Future<String?> getMfaSecret() async {
    return await SecureStorage.read(StorageKeys.mfaSecret);
  }

  String generateOtp(String secret) {
    // Implement TOTP generation
    return _generateTOTP(secret);
  }

  String _generateTOTP(String secret) {
    // Simplified TOTP generation - implement properly in production
    return (DateTime.now().millisecondsSinceEpoch ~/ 30000 % 1000000)
        .toString()
        .padLeft(6, '0');
  }

  bool verifyOtp(String secret, String code) {
    final generated = generateOtp(secret);
    return generated == code;
  }
}