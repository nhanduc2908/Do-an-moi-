// Đường dẫn: mobile_flutter/test/screens/profile_screen_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/screens/profile/profile_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('ProfileScreen Tests', () {
    testWidgets('displays user name', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const ProfileScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.text('User Name'), findsOneWidget);
    });

    testWidgets('displays user email', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const ProfileScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.text('user@example.com'), findsOneWidget);
    });

    testWidgets('has edit profile option', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const ProfileScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.text('Edit Profile'), findsOneWidget);
    });

    testWidgets('has change password option', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const ProfileScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.text('Change Password'), findsOneWidget);
    });

    testWidgets('has logout option', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const ProfileScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.text('Logout'), findsOneWidget);
    });
  });
}