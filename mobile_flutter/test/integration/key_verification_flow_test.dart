// Đường dẫn: mobile_flutter/test/integration/key_verification_flow_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/presentation/screens/key/key_verification_screen.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Key Verification Flow Integration Tests', () {
    testWidgets('can navigate to key verification screen', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      await tester.tap(find.text('Keys'));
      await tester.pumpAndSettle();

      expect(find.byType(KeyVerificationScreen), findsOneWidget);
    });

    testWidgets('shows error for empty key', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: KeyVerificationScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.tap(find.text('Verify Key'));
      await tester.pump();

      expect(find.text('Please enter the verification key'), findsOneWidget);
    });

    testWidgets('valid key shows success', (WidgetTester tester) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: KeyVerificationScreen(),
        ),
      );
      await tester.pumpAndSettle();

      await tester.enterText(find.byType(TextField), 'valid_key_123');
      await tester.tap(find.text('Verify Key'));
      await tester.pumpAndSettle();

      expect(find.text('Key verified successfully!'), findsOneWidget);
    });
  });
}