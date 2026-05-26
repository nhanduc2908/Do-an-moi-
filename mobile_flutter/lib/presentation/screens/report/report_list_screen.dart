import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/report_provider.dart';
import '../../widgets/report/report_card.dart';

class ReportListScreen extends ConsumerStatefulWidget {
  const ReportListScreen({super.key});

  @override
  ConsumerState<ReportListScreen> createState() => _ReportListScreenState();
}

class _ReportListScreenState extends ConsumerState<ReportListScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(reportProvider.notifier).loadReports();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(reportProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Reports'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: state.reports.length,
              itemBuilder: (context, index) {
                final report = state.reports[index];
                return ReportCard(report: report);
              },
            ),
    );
  }
}