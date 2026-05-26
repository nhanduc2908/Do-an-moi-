import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/report_provider.dart';
import '../../widgets/common/custom_button.dart';

class ReportDetailScreen extends ConsumerStatefulWidget {
  const ReportDetailScreen({super.key, required this.reportId});
  final String reportId;

  @override
  ConsumerState<ReportDetailScreen> createState() => _ReportDetailScreenState();
}

class _ReportDetailScreenState extends ConsumerState<ReportDetailScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(reportProvider.notifier).loadReportDetail(widget.reportId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(reportProvider);
    final report = state.currentReport;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Report Details'),
        actions: [
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.share),
            onPressed: () {},
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : report == null
              ? const Center(child: Text('Report not found'))
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            children: [
                              _buildDetailRow('Report Name', report.reportName ?? 'N/A'),
                              _buildDetailRow('Type', report.reportType ?? 'N/A'),
                              _buildDetailRow('Format', report.format?.toUpperCase() ?? 'N/A'),
                              _buildDetailRow('Size', report.fileSizeDisplay),
                              _buildDetailRow('Generated', _formatDate(report.generatedAt)),
                              _buildDetailRow('Downloads', '${report.downloadCount ?? 0}'),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: CustomButton(
                              text: 'Download',
                              onPressed: () {},
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: CustomButton(
                              text: 'Share',
                              onPressed: () {},
                              isOutlined: true,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
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
    return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute}';
  }
}