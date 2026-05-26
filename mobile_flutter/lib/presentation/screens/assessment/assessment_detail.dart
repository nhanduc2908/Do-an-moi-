import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../data/models/assessment_model.dart';
import '../../../providers/assessment_provider.dart';

class AssessmentDetail extends ConsumerStatefulWidget {
  const AssessmentDetail({super.key, required this.assessmentId});
  final String assessmentId;

  @override
  ConsumerState<AssessmentDetail> createState() => _AssessmentDetailState();
}

class _AssessmentDetailState extends ConsumerState<AssessmentDetail> {
  @override
  void initState() {
    super.initState();
    ref.read(assessmentProvider.notifier).loadAssessmentDetail(widget.assessmentId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(assessmentProvider);
    final assessment = state.currentAssessment;
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Assessment Details'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : assessment == null
              ? const Center(child: Text('Assessment not found'))
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text('Score', style: Theme.of(context).textTheme.titleLarge),
                                  Text(
                                    assessment.score?.toStringAsFixed(1) ?? 'N/A',
                                    style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.blue),
                                  ),
                                ],
                              ),
                              const Divider(),
                              _buildDetailRow('Type', assessment.assessmentType ?? 'N/A'),
                              _buildDetailRow('Status', assessment.status ?? 'N/A'),
                              _buildDetailRow('Progress', '${assessment.progress}%'),
                              _buildDetailRow('Started', _formatDate(assessment.startedAt)),
                              _buildDetailRow('Completed', _formatDate(assessment.completedAt)),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Findings', style: TextStyle(fontWeight: FontWeight.bold)),
                              const SizedBox(height: 8),
                              if (assessment.findings != null)
                                ...(assessment.findings as List).map((finding) => Padding(
                                  padding: const EdgeInsets.symmetric(vertical: 4),
                                  child: Row(
                                    children: [
                                      const Icon(Icons.warning, size: 16, color: Colors.orange),
                                      const SizedBox(width: 8),
                                      Expanded(child: Text(finding.toString())),
                                    ],
                                  ),
                                )),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Recommendations', style: TextStyle(fontWeight: FontWeight.bold)),
                              const SizedBox(height: 8),
                              if (assessment.recommendations != null)
                                ...(assessment.recommendations as List).map((rec) => Padding(
                                  padding: const EdgeInsets.symmetric(vertical: 4),
                                  child: Row(
                                    children: [
                                      const Icon(Icons.check_circle, size: 16, color: Colors.green),
                                      const SizedBox(width: 8),
                                      Expanded(child: Text(rec.toString())),
                                    ],
                                  ),
                                )),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          SizedBox(width: 100, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }

  String _formatDate(DateTime? date) {
    if (date == null) return 'N/A';
    return '${date.day}/${date.month}/${date.year}';
  }
}