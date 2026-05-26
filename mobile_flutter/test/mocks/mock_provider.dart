// Đường dẫn: mobile_flutter/test/mocks/mock_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/presentation/providers/auth_provider.dart';
import 'package:security_evaluation_app/presentation/providers/assessment_provider.dart';
import '../helpers/test_data.dart';

class MockAuthNotifier extends Mock implements AuthNotifier {
  static ProviderContainer createMockContainer() {
    final container = ProviderContainer();
    
    container.read(authProvider.notifier).state = AuthState(
      user: TestData.testUser,
      isAuthenticated: true,
    );
    
    return container;
  }
}

class MockAssessmentNotifier extends Mock implements AssessmentNotifier {
  static ProviderContainer createMockContainer() {
    final container = ProviderContainer();
    
    container.read(assessmentProvider.notifier).state = AssessmentState(
      assessments: [TestData.testAssessment],
      isLoading: false,
    );
    
    return container;
  }
}