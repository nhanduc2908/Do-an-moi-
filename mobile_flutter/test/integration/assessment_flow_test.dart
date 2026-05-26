// Đường dẫn: mobile_flutter/test/integration/assessment_flow_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/presentation/screens/assessment/assessment_screen.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Assessment Flow Integration Tests', () {
    testWidgets('can navigate to assessments', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.tap(find.text('Assessments'));
      await tester.pumpAndSettle();

      expect(find.byType(AssessmentScreen), findsOneWidget);
    });
  });
}