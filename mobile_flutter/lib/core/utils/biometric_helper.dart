import 'package:local_auth/local_auth.dart';
import 'package:flutter/services.dart';

class BiometricHelper {
  static final LocalAuthentication _localAuth = LocalAuthentication();
  
  static Future<bool> isBiometricAvailable() async {
    try {
      return await _localAuth.canCheckBiometrics;
    } on PlatformException catch (e) {
      print('Biometric error: $e');
      return false;
    }
  }
  
  static Future<bool> authenticate({
    String reason = 'Authenticate to access your data',
    bool stickyAuth = true,
  }) async {
    try {
      return await _localAuth.authenticate(
        localizedReason: reason,
        options: AuthenticationOptions(
          stickyAuth: stickyAuth,
          biometricOnly: true,
        ),
      );
    } on PlatformException catch (e) {
      print('Authentication error: $e');
      return false;
    }
  }
  
  static Future<List<BiometricType>> getAvailableBiometrics() async {
    try {
      return await _localAuth.getAvailableBiometrics();
    } on PlatformException catch (e) {
      print('Get biometrics error: $e');
      return [];
    }
  }
  
  static bool isFaceAvailable(List<BiometricType> types) {
    return types.contains(BiometricType.face);
  }
  
  static bool isFingerprintAvailable(List<BiometricType> types) {
    return types.contains(BiometricType.fingerprint);
  }
  
  static bool isIrisAvailable(List<BiometricType> types) {
    return types.contains(BiometricType.iris);
  }
  
  static Future<bool> canCheckBiometrics() async {
    try {
      return await _localAuth.canCheckBiometrics;
    } on PlatformException catch (e) {
      return false;
    }
  }
  
  static Future<bool> isDeviceSupported() async {
    try {
      return await _localAuth.isDeviceSupported();
    } on PlatformException catch (e) {
      return false;
    }
  }
  
  static Future<void> stopAuthentication() async {
    await _localAuth.stopAuthentication();
  }
}