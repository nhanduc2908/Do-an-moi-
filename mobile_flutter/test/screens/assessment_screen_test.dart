// Đường dẫn: mobile_flutter/test/screens/assessment_screen_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/screens/assessment/assessment_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('AssessmentScreen Tests', () {
    testWidgets('renders assessment list', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const AssessmentScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.byType(ListView), findsOneWidget);
    });

    testWidgets('shows loading indicator when loading', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const AssessmentScreen()),
      );
      
      expect(find.byType(CircularProgressIndicator), findsOneWidget);
    });

    testWidgets('has add button', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const AssessmentScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.byIcon(Icons.add), findsOneWidget);
    });

    testWidgets('has filter button', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const AssessmentScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.byIcon(Icons.filter_list), findsOneWidget);
    });
  });
}