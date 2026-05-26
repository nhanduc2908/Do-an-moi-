// Đường dẫn: mobile_flutter/test/widget/error_widget_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/common/error_widget.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('ErrorWidget Tests', () {
    testWidgets('displays error message', (WidgetTester tester) async {
      const errorMessage = 'Something went wrong';
      
      await tester.pumpWidget(
        createTestWidget(
          ErrorDisplayWidget(
            message: errorMessage,
            onRetry: () {},
          ),
        ),
      );

      expect(find.text(errorMessage), findsOneWidget);
    });

    testWidgets('displays retry button', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const ErrorDisplayWidget(
            message: 'Error',
            onRetry: null,
          ),
        ),
      );

      expect(find.text('Retry'), findsOneWidget);
    });

    testWidgets('calls onRetry when retry button tapped', (WidgetTester tester) async {
      var retried = false;
      
      await tester.pumpWidget(
        createTestWidget(
          ErrorDisplayWidget(
            message: 'Error',
            onRetry: () => retried = true,
          ),
        ),
      );

      await tester.tap(find.text('Retry'));
      await tester.pump();

      expect(retried, true);
    });

    testWidgets('shows error icon', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const ErrorDisplayWidget(
            message: 'Error',
            onRetry: null,
          ),
        ),
      );

      expect(find.byIcon(Icons.error_outline), findsOneWidget);
    });
  });
}