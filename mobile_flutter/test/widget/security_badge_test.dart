// Đường dẫn: mobile_flutter/test/widget/security_badge_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/common/security_badge.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('SecurityBadge Tests', () {
    testWidgets('displays text correctly', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const SecurityBadge(
            text: 'High Risk',
            color: Colors.red,
          ),
        ),
      );

      expect(find.text('High Risk'), findsOneWidget);
    });

    testWidgets('displays icon when provided', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const SecurityBadge(
            text: 'Secure',
            color: Colors.green,
            icon: Icons.check,
          ),
        ),
      );

      expect(find.byIcon(Icons.check), findsOneWidget);
    });

    testWidgets('applies correct color', (WidgetTester tester) async {
      const color = Colors.red;
      
      await tester.pumpWidget(
        createTestWidget(
          SecurityBadge(
            text: 'Critical',
            color: color,
          ),
        ),
      );

      final container = tester.widget<Container>(find.byType(Container).first);
      final decoration = container.decoration as BoxDecoration;
      
      expect(decoration.color?.value, color.withOpacity(0.2).value);
    });
  });
}