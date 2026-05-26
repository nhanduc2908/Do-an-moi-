// Đường dẫn: mobile_flutter/test/providers/auth_provider_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:security_evaluation_app/presentation/providers/auth_provider.dart';
import '../mocks/mock_repository.dart';
import '../helpers/test_data.dart';

void main() {
  late ProviderContainer container;
  late MockAuthRepository mockRepository;

  setUp(() {
    mockRepository = MockRepositories.createMockAuthRepository();
    container = ProviderContainer();
  });

  tearDown(() {
    container.dispose();
  });

  group('AuthProvider Tests', () {
    test('initial state is unauthenticated', () {
      final state = container.read(authProvider);
      
      expect(state.isAuthenticated, false);
      expect(state.user, null);
      expect(state.isLoading, false);
    });

    test('login updates state on success', () async {
      final notifier = container.read(authProvider.notifier);
      
      await notifier.login('test@example.com', 'password');
      
      final state = container.read(authProvider);
      expect(state.isAuthenticated, true);
      expect(state.user, isNotNull);
    });

    test('logout clears state', () async {
      final notifier = container.read(authProvider.notifier);
      await notifier.login('test@example.com', 'password');
      await notifier.logout();
      
      final state = container.read(authProvider);
      expect(state.isAuthenticated, false);
      expect(state.user, null);
    });
  });
}