import 'dart:async';
import 'token_manager.dart';
import '../../core/utils/logger.dart';

class AuthStateManager {
  final TokenManager _tokenManager;
  final StreamController<bool> _authStateController = StreamController.broadcast();

  AuthStateManager(this._tokenManager);

  Stream<bool> get authStateStream => _authStateController.stream;

  Future<void> checkAuthState() async {
    final hasToken = await _tokenManager.hasValidToken();
    _authStateController.add(hasToken);
    Logger.auth('Auth state checked: ${hasToken ? 'authenticated' : 'unauthenticated'}');
  }

  void emitAuthenticated() {
    _authStateController.add(true);
  }

  void emitUnauthenticated() {
    _authStateController.add(false);
  }

  void dispose() {
    _authStateController.close();
  }
}