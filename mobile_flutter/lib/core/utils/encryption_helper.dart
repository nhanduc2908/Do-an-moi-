import 'dart:convert';
import 'dart:typed_data';
import 'package:pointycastle/export.dart';

class EncryptionHelper {
  static const String _key = 'your-32-character-encryption-key-here!!';
  
  static Uint8List _deriveKey(String password, Uint8List salt) {
    final pbkdf2 = PBKDF2KeyDerivator(HMac(SHA256Digest(), 64))
      ..init(Pbkdf2Parameters(salt, 10000, 32));
    return pbkdf2.process(utf8.encode(password) as Uint8List);
  }
  
  static String encrypt(String plaintext) {
    final salt = generateRandomBytes(32);
    final key = _deriveKey(_key, salt);
    final iv = generateRandomBytes(16);
    
    final cipher = GCMBlockCipher(AESEngine())
      ..init(true, AEADParameters(KeyParameter(key), 128, iv, null));
    
    final ciphertext = Uint8List(cipher.getOutputSize(plaintext.length));
    final len = cipher.processBytes(utf8.encode(plaintext) as Uint8List, 0, plaintext.length, ciphertext, 0);
    cipher.doFinal(ciphertext, len);
    
    final result = Uint8List(salt.length + iv.length + ciphertext.length);
    result.setAll(0, salt);
    result.setAll(salt.length, iv);
    result.setAll(salt.length + iv.length, ciphertext);
    
    return base64.encode(result);
  }
  
  static String decrypt(String ciphertextBase64) {
    final combined = base64.decode(ciphertextBase64);
    final salt = combined.sublist(0, 32);
    final iv = combined.sublist(32, 48);
    final ciphertext = combined.sublist(48);
    
    final key = _deriveKey(_key, salt);
    
    final cipher = GCMBlockCipher(AESEngine())
      ..init(false, AEADParameters(KeyParameter(key), 128, iv, null));
    
    final plaintext = Uint8List(cipher.getOutputSize(ciphertext.length));
    final len = cipher.processBytes(ciphertext, 0, ciphertext.length, plaintext, 0);
    cipher.doFinal(plaintext, len);
    
    return utf8.decode(plaintext);
  }
  
  static Uint8List generateRandomBytes(int length) {
    final secureRandom = SecureRandom('Fortuna')
      ..seed(KeyParameter(
        Uint8List.fromList(DateTime.now().millisecondsSinceEpoch.toString().codeUnits)
      ));
    return secureRandom.nextBytes(length);
  }
  
  static String hashSha256(String input) {
    final digest = SHA256Digest();
    final hash = Uint8List(digest.outputSize);
    digest.process(Uint8List.fromList(utf8.encode(input)), hash);
    return base64.encode(hash);
  }
  
  static String generateRandomKey(int length) {
    final random = generateRandomBytes(length);
    return base64.encode(random);
  }
}