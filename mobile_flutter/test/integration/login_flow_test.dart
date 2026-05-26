// Đường dẫn: mobile_flutter/test/integration/login_flow_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/presentation/screens/auth/login_screen.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Login Flow Tests', () {
    testWidgets('user can enter email and password', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.enterText(find.byType(TextField).first, 'test@example.com');
      await tester.enterText(find.byType(TextField).last, 'password123');

      expect(find.text('test@example.com'), findsOneWidget);
    });

    testWidgets('shows error for invalid credentials', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.tap(find.text('Login'));
      await tester.pump();

      expect(find.text('Please enter email and password'), findsOneWidget);
    });
  });
}