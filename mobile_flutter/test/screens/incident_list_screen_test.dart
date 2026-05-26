// Đường dẫn: mobile_flutter/test/screens/incident_list_screen_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/screens/incident/incident_list_screen.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('IncidentListScreen Tests', () {
    testWidgets('renders incident list', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const IncidentListScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.byType(ListView), findsOneWidget);
    });

    testWidgets('shows loading indicator', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const IncidentListScreen()),
      );
      
      expect(find.byType(CircularProgressIndicator), findsOneWidget);
    });

    testWidgets('has filter button', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const IncidentListScreen()),
      );
      await tester.pumpAndSettle();

      expect(find.byIcon(Icons.filter_list), findsOneWidget);
    });
  });
}