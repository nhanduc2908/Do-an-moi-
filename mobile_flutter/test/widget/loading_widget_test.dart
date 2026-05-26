// Đường dẫn: mobile_flutter/test/widget/loading_widget_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/common/loading_widget.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('LoadingWidget Tests', () {
    testWidgets('renders circular progress indicator', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const LoadingWidget()),
      );

      expect(find.byType(CircularProgressIndicator), findsOneWidget);
    });

    testWidgets('shows message when provided', (WidgetTester tester) async {
      const message = 'Loading data...';
      
      await tester.pumpWidget(
        createTestWidget(LoadingWidget(message: message)),
      );

      expect(find.text(message), findsOneWidget);
    });

    testWidgets('does not show message when not provided', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const LoadingWidget()),
      );

      expect(find.byType(Text), findsNothing);
    });

    testWidgets('centers content', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(const LoadingWidget()),
      );

      final center = tester.widget<Center>(find.byType(Center));
      expect(center, isNotNull);
    });
  });
}