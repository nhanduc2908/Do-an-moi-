// Đường dẫn: mobile_flutter/test/integration/app_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('App Integration Tests', () {
    testWidgets('app starts without crashing', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      expect(find.byType(MaterialApp), findsOneWidget);
    });

    testWidgets('navigates to login screen', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      expect(find.text('Login'), findsWidget);
    });
  });
}