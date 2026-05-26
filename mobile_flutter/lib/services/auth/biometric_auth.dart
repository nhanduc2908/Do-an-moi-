import 'package:local_auth/local_auth.dart';
import '../../core/utils/logger.dart';

class BiometricAuth {
  static final LocalAuthentication _localAuth = LocalAuthentication();

  static Future<bool> isAvailable() async {
    try {
      return await _localAuth.canCheckBiometrics;
    } catch (e) {
      Logger.error('Biometric check failed', e);
      return false;
    }
  }

  static Future<bool> authenticate({String reason = 'Authenticate to access your data'}) async {
    try {
      return await _localAuth.authenticate(
        localizedReason: reason,
        options: const AuthenticationOptions(
          stickyAuth: true,
          biometricOnly: true,
        ),
      );
    } catch (e) {
      Logger.error('Biometric authentication failed', e);
      return false;
    }
  }

  static Future<List<BiometricType>> getAvailableBiometrics() async {
    try {
      return await _localAuth.getAvailableBiometrics();
    } catch (e) {
      Logger.error('Get biometrics failed', e);
      return [];
    }
  }
}