// Đường dẫn: mobile_flutter/test/screens/login_screen_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/screens/auth/login_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('LoginScreen Tests', () {
    testWidgets('renders all input fields', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const LoginScreen()),
      );

      expect(find.byType(TextField), findsNWidgets(2));
      expect(find.text('Login'), findsOneWidget);
    });

    testWidgets('shows error when fields are empty', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const LoginScreen()),
      );

      await tester.tap(find.text('Login'));
      await tester.pump();

      expect(find.text('Please enter email and password'), findsOneWidget);
    });

    testWidgets('navigates to register screen', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const LoginScreen()),
      );

      await tester.tap(find.text('Register'));
      await tester.pumpAndSettle();

      expect(find.text('Register'), findsOneWidget);
    });
  });
}