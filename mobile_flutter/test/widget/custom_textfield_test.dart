// Đường dẫn: mobile_flutter/test/widget/custom_textfield_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/common/custom_textfield.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('CustomTextField Tests', () {
    testWidgets('displays label correctly', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const CustomTextField(
            label: 'Email',
          ),
        ),
      );

      expect(find.text('Email'), findsOneWidget);
    });

    testWidgets('displays hint text', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const CustomTextField(
            label: 'Email',
            hintText: 'Enter your email',
          ),
        ),
      );

      expect(find.text('Enter your email'), findsOneWidget);
    });

    testWidgets('shows error text when provided', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          const CustomTextField(
            label: 'Password',
            errorText: 'Password is required',
          ),
        ),
      );

      expect(find.text('Password is required'), findsOneWidget);
    });

    testWidgets('handles text input', (WidgetTester tester) async {
      final controller = TextEditingController();
      
      await tester.pumpWidget(
        createTestWidget(
          CustomTextField(
            label: 'Name',
            controller: controller,
          ),
        ),
      );

      await tester.enterText(find.byType(TextField), 'John Doe');
      
      expect(controller.text, 'John Doe');
    });
  });
}