// Đường dẫn: mobile_flutter/test/widget/assessment_card_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/presentation/widgets/assessment/assessment_card.dart';
import 'package:security_evaluation_app/data/models/assessment_model.dart';
import '../helpers/test_data.dart';
import '../helpers/test_helpers.dart';

void main() {
  group('AssessmentCard Tests', () {
    late AssessmentModel assessment;

    setUp(() {
      assessment = TestData.testAssessment;
    });

    testWidgets('displays assessment title', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          AssessmentCard(assessment: assessment),
        ),
      );

      expect(find.text(assessment.title!), findsOneWidget);
    });

    testWidgets('displays score', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          AssessmentCard(assessment: assessment),
        ),
      );

      expect(find.text('${assessment.score}%'), findsOneWidget);
    });

    testWidgets('displays status badge', (WidgetTester tester) async {
      await tester.pumpWidget(
        createTestWidget(
          AssessmentCard(assessment: assessment),
        ),
      );

      expect(find.text(assessment.status!), findsOneWidget);
    });
  });
}