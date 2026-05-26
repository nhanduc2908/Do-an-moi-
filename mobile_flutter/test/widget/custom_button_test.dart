// Đường dẫn: mobile_flutter/test/widget/custom_button_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/common/custom_button.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('CustomButton Tests', () {
    testWidgets('renders correctly with text', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const CustomButton(
            text: 'Click Me',
            onPressed: null,
          ),
        ),
      );

      expect(find.text('Click Me'), findsOneWidget);
    });

    testWidgets('calls onPressed when tapped', (WidgetTester tester) async {
      var tapped = false;
      
      await tester.pumpWidget(
        createTestWidget(
          CustomButton(
            text: 'Click Me',
            onPressed: () => tapped = true,
          ),
        ),
      );

      await tester.tap(find.byType(CustomButton));
      await tester.pump();

      expect(tapped, true);
    });

    testWidgets('shows loading indicator when isLoading is true', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const CustomButton(
            text: 'Click Me',
            onPressed: null,
            isLoading: true,
          ),
        ),
      );

      expect(find.byType(CircularProgressIndicator), findsOneWidget);
      expect(find.text('Click Me'), findsNothing);
    });

    testWidgets('has outlined style when isOutlined is true', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const CustomButton(
            text: 'Click Me',
            onPressed: null,
            isOutlined: true,
          ),
        ),
      );

      final button = tester.widget<OutlinedButton>(find.byType(OutlinedButton));
      expect(button, isNotNull);
    });
  });
}