// Đường dẫn: mobile_flutter/test/screens/dashboard_screen_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/screens/home/dashboard_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('DashboardScreen Tests', () {
    testWidgets('renders security score card', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const DashboardScreen()),
      );

      expect(find.text('Security Score'), findsOneWidget);
    });

    testWidgets('renders statistic cards', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const DashboardScreen()),
      );

      expect(find.text('Open Incidents'), findsOneWidget);
      expect(find.text('Vulnerabilities'), findsOneWidget);
    });
  });
}