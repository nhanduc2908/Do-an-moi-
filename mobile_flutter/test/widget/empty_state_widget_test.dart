// Đường dẫn: mobile_flutter/test/widget/empty_state_widget_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/common/empty_state_widget.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('EmptyStateWidget Tests', () {
    testWidgets('displays title and message', (WidgetTester tester) async {
      const title = 'No Data';
      const message = 'There is no data to display';
      
      await tester.pumpWidget(
        createTestWidget(
          const EmptyStateWidget(
            title: title,
            message: message,
          ),
        ),
      );

      expect(find.text(title), findsOneWidget);
      expect(find.text(message), findsOneWidget);
    });

    testWidgets('displays custom icon', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const EmptyStateWidget(
            title: 'Empty',
            message: 'Nothing here',
            icon: Icons.inbox,
          ),
        ),
      );

      expect(find.byIcon(Icons.inbox), findsOneWidget);
    });

    testWidgets('displays action button when provided', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const EmptyStateWidget(
            title: 'Empty',
            message: 'Nothing here',
            actionText: 'Add Item',
            onAction: null,
          ),
        ),
      );

      expect(find.text('Add Item'), findsOneWidget);
    });

    testWidgets('calls onAction when button tapped', (WidgetTester tester) async {
      var actionCalled = false;
      
      await tester.pumpWidget(
        createTestWidget(
          EmptyStateWidget(
            title: 'Empty',
            message: 'Nothing here',
            actionText: 'Add Item',
            onAction: () => actionCalled = true,
          ),
        ),
      );

      await tester.tap(find.text('Add Item'));
      await tester.pump();

      expect(actionCalled, true);
    });
  });
}