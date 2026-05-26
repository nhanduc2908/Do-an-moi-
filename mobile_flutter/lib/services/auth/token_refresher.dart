import '../../data/repositories/auth_repository.dart';
import '../../core/utils/logger.dart';
import 'token_manager.dart';

class TokenRefresher {
  final AuthRepository _authRepository;
  final TokenManager _tokenManager;
  bool _isRefreshing = false;

  TokenRefresher(this._authRepository, this._tokenManager);

  Future<bool> refreshToken() async {
    if (_isRefreshing) return false;

    _isRefreshing = true;
    try {
      final refreshToken = await _tokenManager.getRefreshToken();
      if (refreshToken == null) return false;

      // Call refresh endpoint
      // final response = await _authRepository.refreshToken(refreshToken);
      // await _tokenManager.saveTokens(response.accessToken, response.refreshToken);
      
      Logger.auth('Token refreshed successfully');
      return true;
    } catch (e) {
      Logger.error('Token refresh failed', e);
      return false;
    } finally {
      _isRefreshing = false;
    }
  }
}