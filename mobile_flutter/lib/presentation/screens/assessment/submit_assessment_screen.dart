import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class SubmitAssessmentScreen extends ConsumerStatefulWidget {
  const SubmitAssessmentScreen({super.key, required this.assessmentId});
  final String assessmentId;

  @override
  ConsumerState<SubmitAssessmentScreen> createState() => _SubmitAssessmentScreenState();
}

class _SubmitAssessmentScreenState extends ConsumerState<SubmitAssessmentScreen> {
  final Map<String, TextEditingController> _answerControllers = {};
  final List<String> _criteriaIds = ['CRIT-001', 'CRIT-002', 'CRIT-003'];
  bool _isSubmitting = false;

  @override
  void dispose() {
    for (var controller in _answerControllers.values) {
      controller.dispose();
    }
    super.dispose();
  }

  Future<void> _submitAssessment() async {
    final answers = <String, String>{};
    for (var entry in _answerControllers.entries) {
      answers[entry.key] = entry.value.text;
    }

    setState(() => _isSubmitting = true);
    
    final success = await ref.read(assessmentProvider.notifier)
        .submitAssessment(widget.assessmentId, answers);
    
    setState(() => _isSubmitting = false);
    
    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Assessment submitted successfully')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Submit Assessment'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _criteriaIds.length,
        itemBuilder: (context, index) {
          final criteriaId = _criteriaIds[index];
          _answerControllers.putIfAbsent(criteriaId, () => TextEditingController());
          
          return Card(
            margin: const EdgeInsets.only(bottom: 16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Criteria ${index + 1}',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                  ),
                  const SizedBox(height: 8),
                  const Text('Please provide your assessment for this criteria:'),
                  const SizedBox(height: 12),
                  CustomTextField(
                    controller: _answerControllers[criteriaId]!,
                    label: 'Your response',
                    maxLines: 4,
                  ),
                ],
              ),
            ),
          );
        },
      ),
      bottomNavigationBar: Padding(
        padding: const EdgeInsets.all(16),
        child: CustomButton(
          text: 'Submit Assessment',
          onPressed: _submitAssessment,
          isLoading: _isSubmitting,
        ),
      ),
    );
  }
}