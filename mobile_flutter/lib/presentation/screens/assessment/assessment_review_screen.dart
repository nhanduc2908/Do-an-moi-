import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/common/custom_button.dart';

class AssessmentReviewScreen extends ConsumerStatefulWidget {
  const AssessmentReviewScreen({super.key, required this.assessmentId});
  final String assessmentId;

  @override
  ConsumerState<AssessmentReviewScreen> createState() => _AssessmentReviewScreenState();
}

class _AssessmentReviewScreenState extends ConsumerState<AssessmentReviewScreen> {
  final Map<String, double> _scores = {};
  bool _isSubmitting = false;

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(assessmentProvider);
    final assessment = state.currentAssessment;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Review Assessment'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : assessment == null
              ? const Center(child: Text('Assessment not found'))
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: 3, // Number of criteria
                  itemBuilder: (context, index) {
                    final criteriaId = 'CRIT-00${index + 1}';
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
                            const Text('Sample response text for this criteria...'),
                            const SizedBox(height: 12),
                            const Text('Score:', style: TextStyle(fontWeight: FontWeight.bold)),
                            const SizedBox(height: 8),
                            Row(
                              children: [
                                _buildScoreButton(1, criteriaId),
                                _buildScoreButton(2, criteriaId),
                                _buildScoreButton(3, criteriaId),
                                _buildScoreButton(4, criteriaId),
                                _buildScoreButton(5, criteriaId),
                              ],
                            ),
                            if (_scores[criteriaId] != null)
                              Padding(
                                padding: const EdgeInsets.only(top: 8),
                                child: Text('Selected score: ${_scores[criteriaId]!.toInt()}/5'),
                              ),
                            const SizedBox(height: 8),
                            const Text('Reviewer Comments:', style: TextStyle(fontWeight: FontWeight.bold)),
                            const SizedBox(height: 8),
                            TextField(
                              decoration: const InputDecoration(
                                hintText: 'Enter comments...',
                                border: OutlineInputBorder(),
                              ),
                              maxLines: 3,
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
          text: 'Submit Review',
          onPressed: () {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Review submitted successfully')),
            );
            Navigator.pop(context);
          },
          isLoading: _isSubmitting,
        ),
      ),
    );
  }

  Widget _buildScoreButton(int score, String criteriaId) {
    final isSelected = _scores[criteriaId] == score.toDouble();
    return Expanded(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 4),
        child: OutlinedButton(
          onPressed: () {
            setState(() {
              _scores[criteriaId] = score.toDouble();
            });
          },
          style: OutlinedButton.styleFrom(
            backgroundColor: isSelected ? Colors.blue : null,
            foregroundColor: isSelected ? Colors.white : null,
          ),
          child: Text(score.toString()),
        ),
      ),
    );
  }
}