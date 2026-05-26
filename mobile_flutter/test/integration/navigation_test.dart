// Đường dẫn: mobile_flutter/test/integration/navigation_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/core/routes/route_names.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Navigation Integration Tests', () {
    testWidgets('navigates to login screen', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      expect(find.text('Login'), findsOneWidget);
    });

    testWidgets('navigates to register screen', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.tap(find.text('Register'));
      await tester.pumpAndSettle();

      expect(find.text('Register'), findsOneWidget);
    });
  });
}